const NftCreate = artifacts.require('NftCreate.sol');

/*
 * uncomment accounts to access the test accounts made available by the
 * Ethereum client
 * See docs: https://www.trufflesuite.com/docs/truffle/testing/writing-tests-in-javascript
 */
contract("NftCreate", function (accounts) {
  
  describe('first test', () => {
    let instance;
    
    before(async () => {
      instance = await NftCreate.deployed();
    });

    it('user creates the nft', async () => {
      await instance.mint.sendTransaction("okati",accounts[0],1,"https://ipfs.io/ipfs/QmeyiYN2rMrbRM23ac1zdVRkW9FU8oTsiHVJaSSz8Xmnrm",{from: accounts[0]});
      const temp = await instance.getAddress.call(1);
      assert.equal(temp,accounts[0],"Incorrect owner being stored");
    });
    
  });
  
});
