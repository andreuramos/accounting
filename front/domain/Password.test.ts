import { describe, expect, test } from 'vitest'
import { Password } from './Password'

const PASSWORD_MIN_LENGTH = 4

describe(`Pasword ObjectValue`, () => {
    test(`password needs to have at least ${PASSWORD_MIN_LENGTH} characters`, () => {
        const wrongPassword = '123'

        const passwordOrError = Password.create(wrongPassword)

        expect(passwordOrError.errorValue()).toBe('Password needs to have at least 4 characters.')
    })

    test(`password can be created meeting the above requirements`, () => {
        const somePassword = '1234'

        const passwordOrError = Password.create(somePassword)

        expect(passwordOrError.getValue().value).toBe(somePassword)
    })
})