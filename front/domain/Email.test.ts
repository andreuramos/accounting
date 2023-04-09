import { describe, expect, test } from 'vitest'
import { Email } from './Email'

describe(`Email ValueObject`, () => {
    test(`fails to create an invalid email`, () => {
        const wrongEmail = 'wrongEmail'

        const emailOrError = Email.create(wrongEmail)

        expect(emailOrError.errorValue()).toBe('Email is not valid.')
    })

    test(`email can be created meeting the above requirements`, () => {
        const validEmail = 'foo@bar.com'

        const emailOrError = Email.create(validEmail) 

        expect(emailOrError.getValue().value).toBe(validEmail)
    })
})