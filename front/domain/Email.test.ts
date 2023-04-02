import { describe, expect, test } from 'vitest'
import { Email } from './Email'

describe(`Email ValueObject`, () => {
    test(`email cannot have wrong structure`, () => {
        const wrongEmail = 'wrongEmail'

        const emailOrError = Email.create(wrongEmail)

        expect(emailOrError.errorValue()).toBe('Email has wrong structure.')
    })

    test(`email can be created meeting the above requirements`, () => {
        const validEmail = 'foo@bar.com'

        const emailOrError = Email.create(validEmail) 

        expect(emailOrError.getValue().value).toBe(validEmail)
    })
})