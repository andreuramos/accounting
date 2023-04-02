import { describe, expect, test } from 'vitest'
import { Result } from './Result'

describe(`Result class`, () => {
    test(`getValue method returns the value when Result is ok.`, () => {
        const someValue = 'Ok value'
        const result = Result.ok(someValue)
        
        const returnedValue = result.getValue()

        expect(returnedValue).toBe(someValue)
    })
    test(`getValue method throws error when Result fails`, () => {
        const result = Result.fail('Error description.')

        expect(() => result.getValue()).toThrowError()
    })
    test(`errorValue returns null then Result is ok.`, () => {
        const result = Result.ok()

        const errorValue = result.errorValue()

        expect(errorValue).toBeNull()
    })
    test(`errorValue returns the error description when Result fails`, () => {
        const someErrorDescription = 'Error description.'
        const result = Result.fail(someErrorDescription)

        const returnedErrorDescription = result.errorValue()
        expect(returnedErrorDescription).toBe(someErrorDescription)
    })
})