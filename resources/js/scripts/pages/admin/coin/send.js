import $ from '../../../utils/QuerySelectorHelper.js';
import Currency from '../../../utils/CurrencyHelper.js';


// INPUT ELEMENTS
const inputs = {
  coin      : $.first('#coin'),
  price     : $.first('#price'),
  playerId  : $.first('#playerId'),
};


// COIN INPUT HANDLER
const coinInputHandler = () => {
  const coin      = Number(inputs.coin.value);
  const predefine = coins.find(item => item.coin === coin);
  const price     = predefine?.balance || coin * coinConversion;

  inputs.price.value = Currency.toIDR(price);
};


const PlayerID = {
  element: inputs.playerId,
  timeoutID: null,

  inputHandler(event) {
    clearTimeout(this.timeoutID);
    this.element   = event.target;
    this.timeoutID = setTimeout(
      this._searchByPlayerID.bind(this)
    , 500);
  },

  loadHandler(event) {
    this._getPlayers();
  },

  async _getPlayers() {
    try{
      const endpoint  = '/api/users?players=true';
      const options   = {headers: {'X-Auth-Token': token}};
      const request   = new Request(endpoint, options);
      const response  = await fetch(request);
      const resJson   = await response.json();
      const data      = resJson.data;

      this._renderOptions(data);
    }catch(err) {
      console.log(err);
    }
  },

  async _searchByPlayerID() {
    try{
      const playerID  = this.element.value;
      const endpoint  = `/api/users?player_id=${playerID}`;
      const options   = {headers: {'X-Auth-Token': token}};
      const request   = new Request(endpoint, options);
      const response  = await fetch(request);
      const resJson   = await response.json();

      this._renderPlayerElmnt(resJson.data[0]);
    }catch(err) {
      console.log(err);
    }
  },

  _renderOptions(data) {
    data.forEach((item) =>{
      const option     = document.createElement('option');
      option.value     = item.player.id;
      option.innerText = `${item.player.id} | ${item.username}`;
      this.element.appendChild(option);
    });
  },

  _renderPlayerElmnt(data) {
    const tbody = $.first('tbody');

    if(!data)
      return tbody.innerHTML = '';

    const profile   = data.profile_photo || '/assets/images/icons/empty_profile.png';
    const role      = data.player.is_pro_player ? 'Pro Player' : 'Player';
    tbody.innerHTML = `
      <tr>
        <td class="align-middle">
          <div class="d-flex align-center">
            <img src="${profile}" alt="" width="70" class="mr-3 rounded" />
            <div class="d-flex flex-column justify-content-center">
              <h6 class="m-0 font-weight-bold">${data.name}</h6>
              <small class="d-block">${data.username} (${data.gender?.name || '??'})</small>
              <small class="d-block">
                <a href="//api.whatsapp.com/send?phone=${data.phone}" target="_blank"><u>${data.phone || ''}</u></a>
              </small>
              <small class="d-block">${data.email}</small>
            </div>
          </div>
        </td>
        <td class="align-middle">${data.player.coin}</td>
        <td class="align-middle">${role}</td>
      </tr>
    `;
  }
};


// INITIATE EVENT LISTENER
document.addEventListener('DOMContentLoaded', coinInputHandler);
document.addEventListener('DOMContentLoaded', PlayerID.loadHandler.bind(PlayerID));
inputs.coin.addEventListener('input', coinInputHandler);
inputs.playerId.addEventListener('input', PlayerID.inputHandler.bind(PlayerID));