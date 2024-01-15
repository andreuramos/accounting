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

    test(`can introduce a NIF`, async () => {
        renderRouter('/303-form')
        const input: HTMLInputElement = screen.getByLabelText('nif')
        const someNIF = '12345678Z'

        const user = userEvent.setup()
        await user.type(input, someNIF)

        expect(input.value).toEqual(someNIF)
    })

    test(`can introduce a Razón Social`, async () => {
        renderRouter('/303-form')
        const input: HTMLInputElement = screen.getByLabelText('Razon social')
        const someRazonSocial = 'Juan Nadie'

        const user = userEvent.setup()
        await user.type(input, someRazonSocial)

        expect(input.value).toEqual(someRazonSocial)
    })

    test(`can introduce a Ejercicio`, async () => {
        renderRouter('/303-form')
        const input: HTMLInputElement = screen.getByLabelText('Ejercicio')
        const someEjercicio = '2024'

        const user = userEvent.setup()
        await user.type(input, someEjercicio)

        expect(input.value).toEqual(someEjercicio)
    })

    test(`can introduce a Período`, async () => {
        renderRouter('/303-form')
        const input: HTMLInputElement = screen.getByLabelText('Período')
        const somePeriodo = '1T'

        const user = userEvent.setup()
        await user.type(input, somePeriodo)

        expect(input.value).toEqual(somePeriodo)
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

    describe(`IVA deducible`, () => {
        test(`can introduce Base imponible`, async () => {
            renderRouter('/303-form')
            const input: HTMLInputElement = screen.getByLabelText('Base imponible (Deducible)')
            const someBaseImponible = '1000'

            const user = userEvent.setup()
            await user.type(input, someBaseImponible)

            expect(input.value).toEqual(someBaseImponible)
        })

        test(`can introduce a Cuota`, async () => {
            renderRouter('/303-form')
            const input: HTMLInputElement = screen.getByLabelText('Cuota (Deducible)')
            const someBaseImponible = '210'

            const user = userEvent.setup()
            await user.type(input, someBaseImponible)

            expect(input.value).toEqual(someBaseImponible)
        })
    })
})