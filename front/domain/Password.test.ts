import { describe, expect, test } from 'vitest'
import { Password } from './Password'


describe(`Pasword ObjectValue`, () => {
    test(`password cannot be empty`, () => {
        const emptyPassword = ''

        expect(() => Password.create(emptyPassword)).toThrowError('Password cannot be empty.')
    })

    test(`password can be created meeting the above requirements`, () => {
        const somePassword = '1234'

        const password = Password.create(somePassword)

        expect(password.value).toBe(somePassword)
    })
})