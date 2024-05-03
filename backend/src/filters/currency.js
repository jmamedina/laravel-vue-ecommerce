// export default function currencyUSD(value) {
//   return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' })
//     .format(value);
// }

export default function currencyJPY(value) {
  return new Intl.NumberFormat('ja-JP', { style: 'currency', currency: 'JPY' })
    .format(value);
}