import currency from 'currency.js';


// Format price
const priceFormat = (price) => {
    return currency(price, { symbol: 'Rp.', precision: 0, separator: '.' }).format();
}

const priceFormat2 = (price) => {
    return currency(price, { symbol: '', precision: 0, separator: '.' }).format();
}

const percentPrice = (price, percent) => {
    return price * percent / 100;
}

export {
    priceFormat,
    percentPrice,
    priceFormat2
}
