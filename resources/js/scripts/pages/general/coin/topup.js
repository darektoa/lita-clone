import $ from '../../../utils/QuerySelectorHelper.js';

const inputs = {
  coin: $.first('#coin'),
  price: $.first('#price'),
};

const coinInputHandler = () => {
  const coinId = Number(inputs.coin.value);
  const { price } = coins.find(coin => coin.id === coinId);
  inputs.price.value = price || 0; 
};

document.addEventListener('DOMContentLoaded', coinInputHandler);
inputs.coin.addEventListener('change', coinInputHandler);