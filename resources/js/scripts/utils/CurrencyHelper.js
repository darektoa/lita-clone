const Currency = {
  fromNumber(value, options = {}) {
    return Intl.NumberFormat(
      options.locales || 'en-US', 
      options
    ).format(value);
  },


  comaGroupingToNumber(value) {
    const dotSplit  = value.split('.');
    const comaSplit = dotSplit[0].split(',');
    const integer   = comaSplit.join('');
    const fraction  = dotSplit[1] || 0;
    return Number(`${integer}.${fraction}`);
  },


  dotGroupingToNumber(value) {
    const comaSplit = value.split(',');
    const dotSplit  = comaSplit[0].split('.');
    const integer   = dotSplit.join('');
    const fraction  = comaSplit[1] || 0;
    return Number(`${integer}.${fraction}`);
  },


  toNumber(value, {
    groupSign,
    decimalSign,
  } = {}) {
    const comaIndex = value.indexOf(',');
    const dotIndex  = value.indexOf('.');    
    
    if( groupSign === ',' || 
        decimalSign === '.' || 
        (comaIndex < dotIndex && comaIndex !== -1) || 
        dotIndex === -1
    ) return this.comaGroupingToNumber(value);

    return this.dotGroupingToNumber(value);
  },


  toIDR(value, {
    currencySign = 'standard',
    currencyDisplay = 'symbol',
    minimumFractionDigits = 0,
    maximumFractionDigits = 0,
  } = {}) {
    return Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      currencySign,
      currencyDisplay,
      minimumFractionDigits,
      maximumFractionDigits,
    }).format(value);
  }
}

export default Currency;