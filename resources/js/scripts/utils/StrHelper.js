const Str = {
    element(html) {
        const htmlTrimmed    = html.trim();
        const tempParent     = document.createElement('body');
        tempParent.innerHTML += htmlTrimmed;
        console.log(tempParent.firstElementChild);
        return tempParent.childNodes[0];
    }
};

export default Str;