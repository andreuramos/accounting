import { describe, expect, test } from 'vitest'
import { Password } from './Password'

const PASSWORD_MIN_LENGTH = 4

describe(`Pasword ObjectValue`, () => {
    test(`password needs to have at least ${PASSWORD_MIN_LENGTH} characters`, () => {
        const emptyPassword = '123'

        expect(() => Password.create(emptyPassword)).toThrowError('Password needs to have at least 4 characters.')
    })

    test(`password can be created meeting the above requirements`, () => {
        const somePassword = '1234'

        const password = Password.create(somePassword)

        expect(password.value).toBe(somePassword)
    })
})