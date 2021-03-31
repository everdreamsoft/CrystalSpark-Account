
var CSCasaWeb =  {
    provider:'EverdreamSoft Crystal Suite CS web3',

    web3:'unloaded',
    webXCP:'unloaded',
    addressArray:[],
    authSignature:{
        signedAuthSignature:false,
        signedAuthChallenge:false,
        signedAuthProvider:false,


    },

    blockchainProvider:{

        ethereum:{
            verified:false,

            exist:false,
            locked:true,
            isMetaMask:null,
            unlockWallet:function(){

                ethereum.enable();
            }
        },
        bitcoin:{
            verified:false,
            exist:false,
            locked:false,
        },


    },
    listenerFunction:null,
    logText:'',
    inited:false,
    currentAccount:{
        ethereum:false,
        bitcoin:false,
        firstOasis:false
    },
    printStatus:function(){
        var output = 'Unloaded';

        if (this.loaded){

            output += "Loaded \n";
            output += "Web3 "+ this.isWeb3() +"\n";
            output += "Web XCP "+ this.isWebXCP() +"\n";


        }

        return output ;




    },
    loaded:false,
    isWeb3:function () {

        if (this.web3)return true;

        return false ;
    },
    isWebXCP:function () {

        if (this.webXCP)return true;

        return false ;
    },
    log:function (log) {

        this.logText += log+" \n <br>";
       console.log(log);
        this.notify();

    },
    notify:function (change = false) {

        //document.getElementById("log").innerHTML = this.logText;
       // console.log(this.logText);


        if (this.listenerFunction && change){

            setTimeout(this.listenerFunction, 0);

        }

    },
    onChange:function (handler) {


        this.listenerFunction = handler ;

    },

    init: async function(){


        this.log("CasaWeb init v09");
        this.log(navigator.userAgent);



       // showLog();
       // if (this.inited) return this.inited ;



        if (typeof web3 !== 'undefined') {

            console.log(web3.providers);

            this.blockchainProvider.ethereum.exist = true;
            this.blockchainProvider.ethereum.verified = true;

            this.web3 = web3 ;
            this.log("Web ETH Init");






            if (typeof ethereum !== 'undefined') {
                this.log("ethereum javascript object found");

                ethereum.on('accountsChanged', function (accounts,e) {
                    CSCasaWeb.log("account changed"+accounts);


                    CSCasaWeb.currentAccount.ethereum = accounts ;
                    CSCasaWeb.blockchainProvider.ethereum.locked = false ;


                    CSCasaWeb.notify(true);
                    CSCasaWeb.log("account change");
                })


            }

            else{

                this.log("ethereum javascript object not found ");
            }

            try {
                this.log("Entering try to get account");
                //document.getElementById("account").innerHTML = "please wait...";
               
                 const res = await web3.eth.getAccounts((err, acc) => {

                    CSCasaWeb.log("Eth callback callded");
                    CSCasaWeb.log(acc);
                    CSCasaWeb.log(acc[0]);



                    if (err != null) {

                        this.log("Web3 Ethereum"+err);
                        this.notify(true);
                    } else if (typeof (acc[0]) !== 'undefined') {
                        this.log(typeof (acc[0]));
                        this.currentAccount.ethereum = acc[0] ;
                        this.blockchainProvider.ethereum.locked = false ;
                        this.log("Web ETH found");
                        this.log(acc[0]);
                        this.log(acc);
                        this.notify(true);
                        this.addressArray.push(acc[0])
                        //document.getElementById("account").innerHTML = currentAccount;

                    }
                    else{

                        this.log("account undefined probabely locked");
                        this.blockchainProvider.ethereum.locked = true ;
                        this.notify(true);

                    }
                });
            } catch (e) {

                this.log(e);
                this.log("Error raised");
                this.notify(true);

            }



        }
        else {

            this.web3 = false ;
            this.log('no web ETH web3');
            this.notify(true);
            this.blockchainProvider.ethereum.verified = true;
        }

        this.log("Trying webXCP");
        if (typeof webXCP !== 'undefined') {
            this.log(webXCP);
            this.webXCP = webXCP ;
            this.log("Web XCP Init");

            webXCP.getAccounts(null,(err, bitcoinAddress) => {
                if(err != null){
                    this.log("Web XCP "+err);
                    this.notify(true);
                }else {
                    this.log("Web XCP "+bitcoinAddress);
                    this.currentAccount.bitcoin = bitcoinAddress ;
                    this.addressArray.push(bitcoinAddress);
                    this.notify(true);
                }

            });

            this.blockchainProvider.bitcoin.exist = true;

        }
        else {

            this.webXCP = false ;
            this.blockchainProvider.bitcoin.exist = false;
            this.log("no web XCP found");
            this.notify(true);
        }

        this.blockchainProvider.bitcoin.verified = true;
        this.inited = true ;

        //showLog();

        return true ;




    },

    isMyAddress:async function(address){


        if(this.addressArray.indexOf(address) !== -1)
        {
            return true
        }



        return false


    },

    requestSign:async function(address,message, chain){

        console.log("sigining "+address+"addy "+' '+message+' '+chain);

        if (chain === 'ethereum'){


           let signature = await this.web3.personal.sign(web3.fromUtf8(message), web3.eth.coinbase,
                (err, signature) => {
                    if (err) return err;

                    this.authSignature.signedAuthChallenge = message ;
                    this.authSignature.signedAuthSignature = signature ;
                    this.authSignature.signedAuthProvider = this.currentAccount.ethereum;
                    this.notify(true);
                    return signature;
                });



            return signature ;



        }

        if (chain === 'test'){


            let signature = await this.web3.personal.sign(web3.fromUtf8(message), web3.eth.coinbase,
                (err, signature) => {
                    if (err) return err;

                    this.notify(true);
                    return signature;
                });



            return signature ;

        }






       return "no sign" ;


    },
    requestSign2:async function(message, chain){

        console.log("promising");

        if (chain === 'ethereum'){


           return new Promise((resolve,reject) => {this.web3.personal.sign(web3.fromUtf8(message), web3.eth.coinbase
               ,(err,signature) => {
                   if (err) reject(err)
                   resolve(signature)
                   }
               )}
           )
        }

        if (chain === 'test'){

            let signature = await this.web3.personal.sign(web3.fromUtf8(message), web3.eth.coinbase,
                (err, signature) => {
                    if (err) return err;

                    this.notify(true);
                    return signature;
                });



            return signature ;

        }

       return "no sign" ;


    }





};

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}







/*

function signMessage() {
    if (CSCasaWeb.currentAccount.ethereum != null) {
        CSCasaWeb.log("Try to sign");
        CSCasaWeb.notify();
        try {
            var msg = 'my Message';
            if (msg === undefined || msg === "") {
                alert("please enter a message");
                return;
            }
            var from = CSCasaWeb.currentAccount.ethereum;
            CSCasaWeb.web3.eth.sign(from, msg, function(err, result) {
                if (err !== undefined && err !== null) {
                    CSCasaWeb.log( "error:" + err);
                    CSCasaWeb.notify();
                } else {

                    console.log("signature OKay");

                    CSCasaWeb.log( "signature:" + result);
                    CSCasaWeb.notify();
                }
            })
        } catch (e) {
            CSCasaWeb.log( "signature err:" + e);
            CSCasaWeb.notify();
        }
    } else {
        CSCasaWeb.log( "Account need to be unlockded");
        CSCasaWeb.notify();
    }
}
*/



CSCasaWeb.loaded = true ;







