const $ = {
  first(selector) {
    return document.querySelector(selector);
  },

  
  all(selector) {
    return document.querySelectorAll(selector);
  },
};

export default $;