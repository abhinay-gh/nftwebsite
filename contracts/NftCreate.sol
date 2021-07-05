pragma solidity >=0.4.22 <0.9.0;
// SPDX-License-Identifier: MIT
import "./tokens/nf-token-metadata.sol";
import "./ownable.sol";
 
contract NftCreate is NFTokenMetadata, Ownable {
  uint totalNfts =0;
  struct NFTdetail{
    string NFTname;
    address ownerAddress;
    uint tokenId;
    uint blockNumber;
    uint blockTimeStamp;
    string ipfsUrl;
    //uint ts;
    
  }
  NFTdetail[] nftlist ;
  constructor() {
   // nftName;
    nftSymbol = "SYN";
  }
  mapping (uint256 => NFTdetail) infomap;
  function mint(string memory _name,address _to, uint _tokenId, string calldata _uri) external onlyOwner {
   // nftName = _name;
    super._mint(_to, _tokenId);
    super._setTokenUri(_tokenId, _uri);
   string memory ipfsurl = _uri;
   //string memory bot = "https://ipfs.io/ipfs/";
    //ipfsurl = bot + ipfsurl;
    nftlist.push(NFTdetail(_name,_to,_tokenId, block.number,block.timestamp,ipfsurl));
    infomap[_tokenId] = NFTdetail(_name,_to,_tokenId, block.number,block.timestamp,ipfsurl);
    totalNfts++;
  }
  event eventdetails(
    string nftname,
    address owneraddress,
    uint blocknumber,
    uint blocktimestamp,
    string ipfsurl
  );
  event eventaddress(
    address owneraddress
  );
  event eventlength(
    uint length
  );
  function getAddress(uint256 _tokenId) public 
  //view returns (address)
  {
    emit eventaddress(infomap[_tokenId].ownerAddress);
   // return infomap[_tokenId].ownerAddress;

  }
  function getLength() public 
  //view returns (uint)
  {
    emit  eventlength(totalNfts);
   // return totalNfts;
  }
  function getDetails(uint256 _tokenId) public 
  //view returns (string memory, address)
  {
     emit eventdetails(infomap[_tokenId].NFTname,infomap[_tokenId].ownerAddress,infomap[_tokenId].blockNumber,infomap[_tokenId].blockTimeStamp,infomap[_tokenId].ipfsUrl);
  //  return (infomap[_tokenId].NFTname,infomap[_tokenId].ownerAddress);
  }
  
}

//pragma solidity >=0.4.22 <0.9.0;
/*
import "./ownable.sol";
import "./NFTcreate.sol";
/// @title A title that should describe the contract/interface
/// @author The name of the author
/// @notice Explain to an end user what this does
/// @dev Explain to a developer any extra details

contract NftDetails is Ownable
//, NFTcreate
{
//NFTdetail[] data ;
//NFTcreate nftcreate ;
mapping (address => NFTdetail) store;
struct NFTdetail{
    string NFTname;
    string ownerName;
    uint timeStamp;
    uint blockNum;
    address ownerAddress;
}
/*
constructor (address _contractAddress){
    nftcreate = NFTcreate(_contractAddress);
}

function addDetails (string memory _NFTname, string memory _ownerName, uint _timeStamp, uint _blockNum, address _ownerAddress,address NFTcode) internal onlyOwner {
 store[NFTcode] = NFTdetail(_NFTname,_ownerName,_timeStamp, _blockNum, _ownerAddress); 
}

function getDetails (address NFTcode) public view returns (string memory , string memory ,uint ,uint, address) {
    return (store[NFTcode].NFTname,store[NFTcode].ownerName,store[NFTcode].timeStamp, store[NFTcode].blockNum, store[NFTcode].ownerAddress);
}

}
*/