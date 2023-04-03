import { describe, expect, test } from 'vitest'
import { mock } from 'vitest-mock-extended'
import UserRepository from '@domain/UserRepository'
import { CreateUser } from './CreateUser'


describe(`CreateUser use case`, () => {
    test(`calls repo's add method.`, async () => {
        const repo = mock<UserRepository>()
        const theUser = { email: 'foo@bar.com', password: '1234' }
        const createUser = new CreateUser(repo)

        await createUser.execute(theUser)

        expect(repo.add).toHaveBeenCalledOnce()
    })

    test(`returns error when user already exists.`, async () => {
        const repo = mock<UserRepository>()
        repo.exists.mockReturnValue(Promise.resolve(true))
        const theUser = { email: 'foo@bar.com', password: '1234' }
        const createUser = new CreateUser(repo)

        const result = await createUser.execute(theUser)

        expect(result.value.errorValue().message).toBe('User already exists.')
    })

    test(`returns error when email is invalid.`, async () => {
        const repo = mock<UserRepository>()
        const theUser = { email: 'invalidEmail', password: '1234' }
        const createUser = new CreateUser(repo)

        const result = await createUser.execute(theUser)

        expect(result.value.errorValue().message).toBe('Email is not valid.')
    })

    test('returns no error when user is created.', async () => {
        const repo = mock<UserRepository>()
        const theUser = { email: 'foo@bar.com', password: '1234' }
        const createUser = new CreateUser(repo)

        const result = await createUser.execute(theUser)

        expect(result.value.errorValue()).toBeNull()
    })
})