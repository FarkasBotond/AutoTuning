import { test, expect } from '@playwright/test'

const setAuthState = (page) => {
    return page.addInitScript(() => {
        localStorage.setItem('auth', JSON.stringify({
            token: 'cart-test-token',
            user: {
                id: 1,
                name: 'Cart Test User',
                email: 'cart@example.com',
                role: 'user'
            }
        }))
    })
}

const setCartState = (page, items = []) => {
    return page.addInitScript((cartItems) => {
        localStorage.setItem('race_district_cart', JSON.stringify(cartItems))
    }, items)
}

test.describe('Cart page', () => {
    test('authenticated user can open empty cart page', async ({ page }) => {
        await setAuthState(page)

        await page.goto('/cart')

        await expect(page.getByRole('heading', { name: 'Kosár' })).toBeVisible()
        await expect(page.getByRole('heading', { name: 'A kosár üres' })).toBeVisible()
        await expect(page.getByText('Még nincs termék a kosárban')).toBeVisible()
    })

    test('cart items are loaded from localStorage after refresh', async ({ page }) => {
        await setAuthState(page)
        await setCartState(page, [
            {
                id: 101,
                name: 'Első sport féktárcsa pár',
                brand: 'Zimmermann',
                image: '',
                price: 46500,
                quantity: 2
            }
        ])

        await page.goto('/cart')

        await expect(page.getByText('Első sport féktárcsa pár')).toBeVisible()
        await expect(page.getByText('Zimmermann')).toBeVisible()
        await expect(page.getByText('2')).toBeVisible()
        await expect(page.getByText(/93\s000 Ft/)).toBeVisible()

        await page.reload()

        await expect(page.getByText('Első sport féktárcsa pár')).toBeVisible()
        await expect(page.getByText('Zimmermann')).toBeVisible()
        await expect(page.getByText('2')).toBeVisible()
        await expect(page.getByText(/93\s000 Ft/)).toBeVisible()
    })

    test('user can increase decrease and remove cart item', async ({ page }) => {
        await setAuthState(page)
        await setCartState(page, [
            {
                id: 202,
                name: 'Sport légszűrő',
                brand: 'K&N',
                image: '',
                price: 29990,
                quantity: 1
            }
        ])

        await page.goto('/cart')

        await expect(page.getByText('Sport légszűrő')).toBeVisible()
        await expect(page.getByText('1')).toBeVisible()

        await page.getByRole('button', { name: '+' }).click()

        await expect(page.getByText('2')).toBeVisible()
        await expect(page.getByText(/59\s980 Ft/)).toBeVisible()

        await page.getByRole('button', { name: '-' }).click()

        await expect(page.getByText('1')).toBeVisible()
        await expect(page.getByText(/29\s990 Ft/)).toBeVisible()

        await page.getByRole('button', { name: 'Törlés' }).click()

        await expect(page.getByRole('heading', { name: 'A kosár üres' })).toBeVisible()
    })

    test('checkout button navigates to checkout when cart has item', async ({ page }) => {
        await setAuthState(page)
        await setCartState(page, [
            {
                id: 303,
                name: 'Performance intercooler',
                brand: 'Races',
                image: '',
                price: 129990,
                quantity: 1
            }
        ])

        await page.goto('/cart')

        await page.getByRole('button', { name: 'Tovább a pénztárhoz' }).click()

        await expect(page).toHaveURL('/checkout')
    })
})