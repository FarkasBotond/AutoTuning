import { test, expect } from '@playwright/test'

const mockCompanies = [
    {
        id: 201,
        name: 'No Website Tuning',
        description: 'Teszt tuning cég weboldal nélkül.',
        website_url: null,
        image_url: '/images/companies/maxton.png'
    },
    {
        id: 202,
        name: 'Website Tuning',
        description: 'Teszt tuning cég weboldallal.',
        website_url: 'https://example.com/website-tuning',
        image_url: '/images/companies/brabus.png'
    }
]

const mockCompanyEndpoints = async (page) => {
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

        if (pathname.startsWith('/api/tuning-companies/')) {
            const companyId = Number(pathname.split('/').pop())
            const company = mockCompanies.find((item) => item.id === companyId)

            if (!company) {
                await route.fulfill({
                    status: 404,
                    contentType: 'application/json',
                    body: JSON.stringify({
                        message: 'A tuning cég nem található.'
                    })
                })
                return
            }

            await route.fulfill({
                status: 200,
                contentType: 'application/json',
                body: JSON.stringify({
                    data: company
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

test.describe('Tuning cég részletes oldal extra esetek', () => {
    test.beforeEach(async ({ page }) => {
        await mockCompanyEndpoints(page)
    })

    test('ha a cégnek nincs weboldala, akkor a Weboldal gomb nem jelenik meg a kártyán', async ({ page }) => {
        await page.goto('/CompaniesPage')

        const companyCard = page.locator('article').filter({
            hasText: 'No Website Tuning'
        })

        await expect(companyCard).toBeVisible()
        await expect(companyCard.getByText('Teszt tuning cég weboldal nélkül.')).toBeVisible()
        await expect(companyCard.getByRole('link', { name: 'Weboldal' })).toHaveCount(0)
    })

    test('ha a cégnek nincs weboldala, akkor a részletes oldalon sem jelenik meg a külső weboldal gomb', async ({ page }) => {
        await page.goto('/company/201')

        await expect(page.getByRole('heading', { name: 'No Website Tuning' })).toBeVisible()
        await expect(page.getByText('Teszt tuning cég weboldal nélkül.')).toBeVisible()
        await expect(page.getByRole('link', { name: 'Külső weboldal megnyitása' })).toHaveCount(0)
    })

    test('ha a cégnek van weboldala, akkor a részletes oldalon megjelenik a külső weboldal gomb', async ({ page }) => {
        await page.goto('/company/202')

        const websiteLink = page.getByRole('link', {
            name: 'Külső weboldal megnyitása'
        })

        await expect(page.getByRole('heading', { name: 'Website Tuning' })).toBeVisible()
        await expect(websiteLink).toBeVisible()
        await expect(websiteLink).toHaveAttribute('href', 'https://example.com/website-tuning')
    })

    test('nem létező tuning cég esetén hibaüzenet jelenik meg', async ({ page }) => {
        await page.goto('/company/999')

        await expect(page.getByText(/nem található|Nem sikerült betölteni/i)).toBeVisible()
    })
})