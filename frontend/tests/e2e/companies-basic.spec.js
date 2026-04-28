import { test, expect } from '@playwright/test'

const mockCompanies = [
    {
        id: 1,
        name: 'Maxton Design',
        description: 'Karosszériakitek, splitterek és aerodinamikai elemek gyártása.',
        website_url: 'https://maxtondesign.com',
        image_url: '/images/companies/maxton.png'
    },
    {
        id: 2,
        name: 'ABT Sportsline',
        description: 'Audi/VW/Skoda/Seat tuning, teljesítmény- és optikai fejlesztések.',
        website_url: 'https://www.abt-sportsline.com',
        image_url: '/images/companies/abt.png'
    }
]

const mockCompaniesEndpoints = async (page) => {
    await page.route('**/api/**', async (route) => {
        const url = new URL(route.request().url())
        const pathname = url.pathname

        if (pathname === '/api/tuning-companies') {
            await route.fulfill({
                status: 200,
                contentType: 'application/json',
                body: JSON.stringify({
                    data: mockCompanies
                })
            })
            return
        }

        if (pathname === '/api/tuning-companies/1') {
            await route.fulfill({
                status: 200,
                contentType: 'application/json',
                body: JSON.stringify({
                    data: mockCompanies[0]
                })
            })
            return
        }

        if (pathname === '/api/tuning-companies/2') {
            await route.fulfill({
                status: 200,
                contentType: 'application/json',
                body: JSON.stringify({
                    data: mockCompanies[1]
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

test.describe('Tuning cégek alap működés', () => {
    test.beforeEach(async ({ page }) => {
        await mockCompaniesEndpoints(page)
    })

    test('a tuning cégek oldal megjeleníti a cégeket', async ({ page }) => {
        await page.goto('/CompaniesPage')

        await expect(page.getByRole('heading', { name: 'Tuning cégek' })).toBeVisible()
        await expect(page.getByText('Maxton Design')).toBeVisible()
        await expect(page.getByText('ABT Sportsline')).toBeVisible()
    })

    test('az Adatlap gomb átvisz a cég részletes oldalára', async ({ page }) => {
        await page.goto('/CompaniesPage')

        const companyCard = page.locator('article').filter({
            hasText: 'Maxton Design'
        })

        await companyCard.getByRole('link', { name: 'Adatlap' }).click()

        await expect(page).toHaveURL(/\/company\/1/)
        await expect(page.getByRole('heading', { name: 'Maxton Design' })).toBeVisible()
    })

    test('a cég részletes oldalon megjelenik a külső weboldal gomb', async ({ page }) => {
        await page.goto('/company/1')

        const websiteLink = page.getByRole('link', {
            name: 'Külső weboldal megnyitása'
        })

        await expect(websiteLink).toBeVisible()
        await expect(websiteLink).toHaveAttribute('href', 'https://maxtondesign.com')
    })
})