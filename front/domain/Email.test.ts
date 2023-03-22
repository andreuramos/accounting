import { describe, expect, test } from 'vitest'
import Email from './Email'

describe.only(`Email ValueObject`, () => {
    test(`email cannot be empty`, () => {
        const emptyString = ''

        expect(() => Email.create(emptyString)).toThrow('Email has wrong structure.')
    })
    test(`email cannot have wrong structure`, () => {
        const wrongEmail = 'wrongEmail'

        expect(() => Email.create(wrongEmail)).toThrow('Email has wrong structure.')
    })
    test(`email can be created meeting the above requirements`, () => {
        const validEmail = 'foo@bar.com'

        const email = Email.create(validEmail) 

        expect(email.value).toBe(validEmail)
    })
})