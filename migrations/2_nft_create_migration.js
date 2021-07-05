var NftCreate = artifacts.require('NftCreate.sol');
module.exports = function(_deployer) {
  // Use deployer to state migration tasks.
  _deployer.deploy(NftCreate);
};
