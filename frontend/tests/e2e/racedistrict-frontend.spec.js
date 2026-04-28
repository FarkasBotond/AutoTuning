import { test, expect } from '@playwright/test'

test.describe('RaceDistrict frontend tests', () => {
  test('home page loads product catalog', async ({ page }) => {
    await page.goto('/')

    await expect(page.getByText('KATEGÓRIÁK')).toBeVisible()
    await expect(page.getByText('GYÁRTÓK')).toBeVisible()
    await expect(page.getByText('Termékszűrő')).toBeVisible()
    await expect(page.getByRole('button', { name: 'Szűrés' })).toBeVisible()
  })

  test('search input is visible in the header', async ({ page }) => {
    await page.goto('/')

    const searchInput = page.getByPlaceholder('Termék, márka vagy kulcsszó...')
    await expect(searchInput).toBeVisible()

    await searchInput.fill('stage')
    await expect(searchInput).toHaveValue('stage')
  })

  test('product filter fields can be filled', async ({ page }) => {
    await page.goto('/')

    await page.getByPlaceholder('Pl. turbó, Brembo, Audi').fill('ECU')
    await page.getByPlaceholder('Pl. 50000').fill('50000')
    await page.getByPlaceholder('Pl. 300000').fill('300000')

    await expect(page.getByPlaceholder('Pl. turbó, Brembo, Audi')).toHaveValue('ECU')
    await expect(page.getByPlaceholder('Pl. 50000')).toHaveValue('50000')
    await expect(page.getByPlaceholder('Pl. 300000')).toHaveValue('300000')
  })

  test('filter reset button clears filter inputs', async ({ page }) => {
    await page.goto('/')

    const searchInput = page.getByPlaceholder('Pl. turbó, Brembo, Audi')
    const minPriceInput = page.getByPlaceholder('Pl. 50000')
    const maxPriceInput = page.getByPlaceholder('Pl. 300000')

    await searchInput.fill('turbo')
    await minPriceInput.fill('50000')
    await maxPriceInput.fill('300000')

    await page.getByRole('button', { name: 'Szűrők törlése' }).click()

    await expect(searchInput).toHaveValue('')
    await expect(minPriceInput).toHaveValue('')
    await expect(maxPriceInput).toHaveValue('')
  })

  test('category menu displays tuning categories', async ({ page }) => {
    await page.goto('/')

    await expect(page.getByText('Motor és teljesítménynövelés')).toBeVisible()
    await expect(page.getByText('Kipufogó és szívórendszer')).toBeVisible()
    await expect(page.getByText('Futómű és kormányzás')).toBeVisible()
    await expect(page.getByText('Fékek')).toBeVisible()
  })

  test('manufacturer tab can be opened', async ({ page }) => {
    await page.goto('/')

    await page.getByText('GYÁRTÓK').click()

    await expect(page.getByText('Audi')).toBeVisible()
    await expect(page.getByText('Bmw')).toBeVisible()
  })

  test('guest can add a product to the cart', async ({ page }) => {
    await page.goto('/')

    const cartButton = page.getByRole('button', { name: 'Kosárba' }).first()
    await expect(cartButton).toBeVisible()

    await cartButton.click()

    await expect(page.getByText('hozzáadva a kosárhoz')).toBeVisible()
  })

  test('cart page opens and shows empty cart state or cart summary', async ({ page }) => {
    await page.goto('/cart')

    await expect(page.getByText('Kosár')).toBeVisible()

    const emptyCartText = page.getByText('A kosár üres')
    const summaryText = page.getByText('Összesítő')

    await expect(emptyCartText.or(summaryText)).toBeVisible()
  })

  test('checkout page redirects back to cart when cart is empty', async ({ page }) => {
    await page.goto('/checkout')

    await expect(page).toHaveURL(/.*cart.*/)
    await expect(page.getByText('Kosár')).toBeVisible()
  })

  test('registration page shows validation related fields', async ({ page }) => {
    await page.goto('/register')

    await expect(page.getByText('Regisztráció')).toBeVisible()
    await expect(page.getByLabel('Név')).toBeVisible()
    await expect(page.getByLabel('Email')).toBeVisible()
    await expect(page.getByLabel('Jelszó')).toBeVisible()
  })
})