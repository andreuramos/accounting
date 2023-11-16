import { beforeEach, describe, expect, test } from 'vitest'
import { RouterProvider, createMemoryRouter } from 'react-router-dom'
import { routes } from 'src/routes'
import { render, screen } from '@testing-library/react'
import '@testing-library/jest-dom'
import userEvent from '@testing-library/user-event'

const renderRouter = (url: string) => {
    const router = createMemoryRouter(routes, { initialEntries: [url] })
    render(<RouterProvider router={router} />)
}

describe(`303 Form view`, async () => {
    test(`Can navigate to page`, () => {
        renderRouter('/303-form')

        expect(screen.getByText('Formulario 303')).toBeInTheDocument()
    })

    describe(`calculate 21% IVA of base imponible`, () => {
        let input: HTMLInputElement
        let calculatedCuota: HTMLInputElement

        beforeEach(() => {
            renderRouter('/303-form')
            input = screen.getByLabelText('Base imponible')
            calculatedCuota = screen.getByLabelText('Cuota')
        })

        test.each([
            ['1000', '210'],
            ['500', '105']
        ])(`For base imponible of %i, should calculate %i`, async (base, expectedCuota) => {

            const user = userEvent.setup()
            await user.type(input, base)

            expect(calculatedCuota.value).toEqual(expectedCuota)
        })

        test(`Cuota field should be read-only`, () => {
            expect(calculatedCuota).toHaveAttribute('readOnly')
        })
    })
})