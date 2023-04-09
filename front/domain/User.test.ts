import { describe, expect, test } from 'vitest'
import { User } from '@domain/User'
import { Email } from './Email'
import { Password } from './Password'


describe('User entity', () => {

    test(`user can be created with an email and password.`, () => {
        const correctEmail = 'foo@bar.com'
        const email = Email.create(correctEmail)
        const correctPassword = Password.create('1234')

        const user = User.create(email.getValue(), correctPassword.getValue())

        expect(user.getValue().email).toBe(correctEmail)
    })
})