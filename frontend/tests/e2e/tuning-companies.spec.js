import { test, expect } from '@playwright/test'

const mockCompanies = [
    {
        id: 101,
        name: 'Playwright Performance',
        description: 'Teszt tuning cég teljesítménynöveléshez.',
        website_url: 'https://example.com/playwright-performance',
        image_url: '/images/companies/maxton.png'
    },
    {
        id: 102,
        name: 'Mock Design',
        description: 'Teszt karosszéria és optikai tuning.',
        website_url: 'https://example.com/mock-design',
        image_url: '/images/companies/brabus.png'
    },
    {
        id: 103,
        name: 'E2E Motorsport',
        description: 'Teszt motorsport alkatrészek és kiegészítők.',
        website_url: 'https://example.com/e2e-motorsport',
        image_url: '/images/companies/sparco.png'
    }
]

const mockApiEndpoints = async (page) => {
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
                        message: 'Tuning cég nem található.'
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

test.describe('Tuning cégek oldal', () => {
    test.beforeEach(async ({ page }) => {
        await mockApiEndpoints(page)
    })

    test('Mutass mindent gomb átvisz a tuning cégek oldalra', async ({ page }) => {
        await page.goto('/')

        await page.getByRole('link', { name: 'Mutass mindent' }).click()

        await expect(page).toHaveURL('/CompaniesPage')
        await expect(page.getByRole('heading', { name: 'Tuning cégek' })).toBeVisible()
    })

    test('a cégek megjelennek API mockból', async ({ page }) => {
        await page.goto('/CompaniesPage')

        await expect(page.getByRole('heading', { name: 'Tuning cégek' })).toBeVisible()

        for (const company of mockCompanies) {
            await expect(page.getByText(company.name).first()).toBeVisible()
            await expect(page.getByText(company.description).first()).toBeVisible()
        }
    })

    test('mindegyik mockolt céghez megjelenik az Adatlap gomb', async ({ page }) => {
        await page.goto('/CompaniesPage')

        for (const company of mockCompanies) {
            const companyCard = page.locator('article').filter({
                hasText: company.name
            })

            await expect(companyCard).toBeVisible()
            await expect(companyCard.getByRole('link', { name: 'Adatlap' })).toBeVisible()
        }
    })

    test('Adatlap gomb átvisz a company detail oldalra', async ({ page }) => {
        await page.goto('/CompaniesPage')

        const companyCard = page.locator('article').filter({
            hasText: 'Playwright Performance'
        })

        await companyCard.getByRole('link', { name: 'Adatlap' }).click()

        await expect(page).toHaveURL('/company/101')
        await expect(page.getByText('Tuning cég adatlap')).toBeVisible()
        await expect(page.getByRole('heading', { name: 'Playwright Performance' })).toBeVisible()
        await expect(page.getByText('Teszt tuning cég teljesítménynöveléshez.')).toBeVisible()
    })

    test('külső weboldal gomb látszik a céges kártyán', async ({ page }) => {
        await page.goto('/CompaniesPage')

        const companyCard = page.locator('article').filter({
            hasText: 'Playwright Performance'
        })

        const websiteLink = companyCard.getByRole('link', { name: 'Weboldal' })

        await expect(websiteLink).toBeVisible()
        await expect(websiteLink).toHaveAttribute('href', 'https://example.com/playwright-performance')
    })

    test('külső weboldal gomb látszik a részletes oldalon is', async ({ page }) => {
        await page.goto('/company/101')

        const websiteLink = page.getByRole('link', {
            name: 'Külső weboldal megnyitása'
        })

        await expect(websiteLink).toBeVisible()
        await expect(websiteLink).toHaveAttribute('href', 'https://example.com/playwright-performance')
    })
})