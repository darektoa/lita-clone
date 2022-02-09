import $ from '../../../utils/QuerySelectorHelper.js';
import Currency from '../../../utils/CurrencyHelper.js';

const inputs = {
  coin: $.first('#coin'),
  price: $.first('#price'),
};

const coinInputHandler = () => {
  const coin      = Number(inputs.coin.value);
  const predefine = coins.find(item => item.coin === coin);
  const price     = predefine?.balance || coin * coinConversion;

  inputs.price.value = Currency.toIDR(price);
};

document.addEventListener('DOMContentLoaded', coinInputHandler);
inputs.coin.addEventListener('input', coinInputHandler);