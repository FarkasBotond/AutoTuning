import { defineConfig, devices } from '@playwright/test'
import { existsSync } from 'node:fs'

const resolveExecutablePath = () => {
  if (process.env.PLAYWRIGHT_CHROMIUM_EXECUTABLE_PATH) {
    return process.env.PLAYWRIGHT_CHROMIUM_EXECUTABLE_PATH
  }

  const candidates = ['/usr/bin/chromium', '/usr/bin/chromium-browser']
  return candidates.find((candidate) => existsSync(candidate))
}

const detectedExecutablePath = resolveExecutablePath()

export default defineConfig({
  testDir: './tests/e2e',
  fullyParallel: true,
  retries: 0,
  workers: 1,
  reporter: 'html',
  use: {
    baseURL: 'http://localhost:4173',
    trace: 'on-first-retry'
  },
  projects: [
    {
      name: 'chromium',
      use: {
        ...devices['Desktop Chrome'],
        browserName: 'chromium',
        ...(detectedExecutablePath
          ? {
              launchOptions: {
                executablePath: detectedExecutablePath
              }
            }
          : {})
      }
    }
  ],
  webServer: {
    command: 'pnpm exec vite --host 0.0.0.0 --port 4173 --strictPort',
    url: 'http://127.0.0.1:4173',
    reuseExistingServer: true,
    timeout: 180000,
    stdout: 'pipe',
    stderr: 'pipe'
  }
})
