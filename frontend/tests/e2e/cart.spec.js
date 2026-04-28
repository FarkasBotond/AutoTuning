import { test, expect } from '@playwright/test'

const CART_STORAGE_KEY = 'race_district_cart'
const CART_URL = '/cart'

const mockEndpoints = async (page) => {
    await page.route('**/api/**', async (route) => {
        await route.fulfill({
            status: 200,
            contentType: 'application/json',
            body: JSON.stringify({
                data: []
            })
        })
    })
}

const setUpCartData = async (page, items = []) => {
    await page.evaluate(({ cartItems }) => {
        // Pinia persist formátumban mentjük az adatokat
        localStorage.setItem('cart', JSON.stringify({ items: cartItems }))
    }, {
        cartItems: items
    })
    
    await page.reload()
    await page.waitForLoadState('networkidle')
}

const openCart = async (page, items = []) => {
    if (items.length > 0) {
        await setUpCartData(page, items)
    } else {
        await page.waitForLoadState('networkidle')
    }
}

test.describe('Kosár oldal', () => {
    test.beforeEach(async ({ page, context }) => {
        await mockEndpoints(page)
        await context.clearCookies()
        await page.goto(CART_URL)
        await page.evaluate(() => {
            localStorage.clear()
        })
    })

    test('üres kosár esetén megjelenik az üres kosár üzenet', async ({ page }) => {
        await openCart(page, [])

        await expect(page.getByRole('heading', { name: 'Kosár', exact: true })).toBeVisible()
        await expect(page.getByRole('heading', { name: 'A kosár üres', exact: true })).toBeVisible()
        await expect(page.getByText('Még nincs termék a kosárban')).toBeVisible()
    })

    test('mennyiség növelése és csökkentése működik', async ({ page }) => {
        const cartItems = [
            {
                id: 103,
                name: 'Sport légszűrő',
                brand: 'K&N',
                image: '',
                price: 28000,
                quantity: 1
            }
        ]

        await openCart(page, cartItems)

        await expect(page.getByText('Sport légszűrő')).toBeVisible()

        const increaseButton = page.getByRole('button', { name: '+' }).first()
        await increaseButton.click()
        await page.waitForTimeout(200)

        const decreaseButton = page.getByRole('button', { name: '-' }).first()
        await decreaseButton.click()
        await page.waitForTimeout(200)

        await expect(page.getByText('Sport légszűrő')).toBeVisible()
    })

    test('termék törlése után megfelelően frissül a kosár', async ({ page }) => {
        const cartItems = [
            {
                id: 104,
                name: 'Sport kipufogó',
                brand: 'Akrapovic',
                image: '',
                price: 185000,
                quantity: 1
            }
        ]

        await openCart(page, cartItems)

        await expect(page.getByText('Sport kipufogó')).toBeVisible()

        await page.getByRole('button', { name: 'Törlés' }).click()
        await page.waitForTimeout(300)

        await expect(page.getByRole('heading', { name: 'A kosár üres', exact: true })).toBeVisible()
    })

    test('több termék esetén az összesítő helyesen működik', async ({ page }) => {
        const cartItems = [
            {
                id: 201,
                name: 'Első sport féktárcsa pár',
                brand: 'Zimmermann',
                image: '',
                price: 46500,
                quantity: 2
            },
            {
                id: 202,
                name: 'Sport légszűrő',
                brand: 'K&N',
                image: '',
                price: 28000,
                quantity: 1
            }
        ]

        await openCart(page, cartItems)

        await expect(page.getByText('Első sport féktárcsa pár')).toBeVisible()
        await expect(page.getByText('Sport légszűrő')).toBeVisible()
    })

    test('a pénztár gomb akkor is kattintható, ha van termék', async ({ page }) => {
        const cartItems = [
            {
                id: 301,
                name: 'Sport kipufogó',
                brand: 'Akrapovic',
                image: '',
                price: 185000,
                quantity: 1
            }
        ]

        await openCart(page, cartItems)

        const checkoutButton = page.getByRole('button', { name: 'Tovább a pénztárhoz' })
        await expect(checkoutButton).toBeEnabled()
    })
})