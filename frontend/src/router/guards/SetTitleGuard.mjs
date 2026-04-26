export function setTitle(to, from, next) {
  const pageTitle = to.meta?.title || 'RaceDistrict'
  document.title = pageTitle
  next()
}
