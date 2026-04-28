import { test, expect } from '@playwright/test'

const mockProducts = [
    {
        id: 1,
        name: 'Teszt sport féktárcsa',
        tuning_company: 'EBC Brakes',
        image: '',
        badge: 'Újdonság',
        old_price: null,
        price: 46500,
        is_in_stock: true,
        car_model_id: 1,
        service_category_id: 4,
        car_model: {
            id: 1,
            brand_id: 1,
            model: 'A4 B9',
            brand: {
                id: 1,
                name: 'Audi'
            }
        },
        service_category: {
            id: 4,
            name: 'Fékek'
        }
    },
    {
        id: 2,
        name: 'Teszt carbon diffúzor',
        tuning_company: 'Maxton Design',
        image: '',
        badge: 'Akció',
        old_price: null,
        price: 89990,
        is_in_stock: true,
        car_model_id: 2,
        service_category_id: 6,
        car_model: {
            id: 2,
            brand_id: 2,
            model: 'Golf 7',
            brand: {
                id: 2,
                name: 'Volkswagen'
            }
        },
        service_category: {
            id: 6,
            name: 'Külső kiegészítők'
        }
    }
]

const mockSearchEndpoints = async (page) => {
    await page.route('**/api/**', async (route) => {
        const url = new URL(route.request().url())
        const pathname = url.pathname

        if (pathname === '/api/tuning-products') {
            await route.fulfill({
                status: 200,
                contentType: 'application/json',
                body: JSON.stringify({
                    data: mockProducts
                })
            })
            return
        }

        if (pathname === '/api/car-brands') {
            await route.fulfill({
                status: 200,
                contentType: 'application/json',
                body: JSON.stringify({
                    data: [
                        { id: 1, name: 'Audi' },
                        { id: 2, name: 'Volkswagen' }
                    ]
                })
            })
            return
        }

        if (pathname === '/api/car-models') {
            await route.fulfill({
                status: 200,
                contentType: 'application/json',
                body: JSON.stringify({
                    data: [
                        { id: 1, brand_id: 1, model: 'A4 B9' },
                        { id: 2, brand_id: 2, model: 'Golf 7' }
                    ]
                })
            })
            return
        }

        await route.fulfill({
            status: 200,
            contentType: 'application/json',
            body: JSON.stringify({
                data: []
            })
        })
    })
}

test.describe('Keresés alap működés', () => {
    test.beforeEach(async ({ page }) => {
        await mockSearchEndpoints(page)
    })

    test('a keresőmezőből átvisz a keresési oldalra', async ({ page }) => {
        await page.goto('/')

        await page.getByPlaceholder('Termék, márka vagy kulcsszó...').fill('fek')
        await page.keyboard.press('Enter')

        await expect(page).toHaveURL(/SearchPage/)
        await expect(page).toHaveURL(/q=fek/)
    })

    test('a keresési oldal megjeleníti a találatokat', async ({ page }) => {
        await page.goto('/SearchPage?q=fek')

        await expect(page.getByText('Teszt sport féktárcsa')).toBeVisible()
    })

    test('ékezet nélküli keresésnél is megjelenhet az ékezetes kategóriájú termék', async ({ page }) => {
        await page.goto('/SearchPage?q=fek')

        await expect(page.getByText('Teszt sport féktárcsa')).toBeVisible()
        await expect(page.getByText('EBC Brakes')).toBeVisible()
    })
})