import { beforeEach, expect, test } from 'vitest'
import User from '@domain/User'

test(`user cannot be created without an email`, () => {
    const wrongEmail = ''
    const anyPassword = '1234'

    expect(() => new User(wrongEmail, anyPassword)).toThrowError()
})

test(`user cannot be created without a password`, () => {
    const anyEmail = 'foo@bar.com'
    const wrongPassword = ''

    expect(() => new User(anyEmail, wrongPassword)).toThrowError()
})

test(`user cannot be created with a wrong email`, () => {
    const wrongEmail = 'wrongEmail'
    const anyPassword = '1234'

    expect(() => new User(wrongEmail, anyPassword)).toThrowError()
})

test(`user can be created meeting all the above requirements`, () => {
    const correctEmail = 'foo@bar.com'
    const correctPassword = '1234'
    
    expect(() => new User(correctEmail, correctPassword)).not.toThrowError()
})