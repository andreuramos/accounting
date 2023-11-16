import { describe, expect, test } from 'vitest'
import { RouterProvider, createMemoryRouter } from 'react-router-dom'
import { routes } from 'src/routes'
import { render, screen } from '@testing-library/react'
import '@testing-library/jest-dom'

describe(`303 Form view`, () => {
    test(`Can navigate to page`, () => {
        const router = createMemoryRouter(routes, { initialEntries: ['/303-form'] })
        render(<RouterProvider router={router} />)
        expect(screen.getByText('Formulario 303')).toBeInTheDocument()
    })
})