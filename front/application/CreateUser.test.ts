import { describe, expect, test } from 'vitest'
import { mock } from 'vitest-mock-extended'
import { User } from '@domain'
import UserRepository from '@domain/UserRepository'
import InMemoryUserRepository from '@infrastructure/InMemoryUserRepository'
import { CreateUser } from './CreateUser'


describe(`CreateUser use case`, () => {
    test(`calls repo's add method.`, async () => {
        const repo = mock<UserRepository>()
        const theUser = { email: 'foo@bar.com', password: '1234' }
        const createUser = new CreateUser(repo)

        await createUser.execute(theUser)

        expect(repo.add).toHaveBeenCalledOnce()
    })

    test(`prevents user creation when already exists.`, async () => {
        const repo = mock<UserRepository>()
        repo.exists.mockReturnValue(Promise.resolve(true))
        const theUser = { email: 'foo@bar.com', password: '1234' }
        const createUser = new CreateUser(repo)

        expect(createUser.execute(theUser)).rejects.toEqual('User already exists.')
    })



    // This is a repo test
    test(`user is added.`, async () => {
        const repo = new InMemoryUserRepository()
        const theUser = { email: 'foo@bar.com', password: '1234' }
        const createUser = new CreateUser(repo)

        await createUser.execute(theUser)
        const createdUser = await repo.findByEmail(theUser.email)

        expect(createdUser).instanceOf(User)
        expect(createdUser!.email).toBe(theUser.email)
    })
})