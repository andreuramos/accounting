import { describe, expect, test } from 'vitest'
import { mock } from 'vitest-mock-extended'
import { User } from '@domain'
import UserRepository from '@domain/UserRepository'
import InMemoryUserRepository from '@infrastructure/InMemoryUserRepository'
import { CreateUser } from './CreateUser'


describe(`CreateUser use case`, () => {
    test(`calls repo's add method.`, () => {
        const repo = mock<UserRepository>()
        const email = 'foo@bar.com'
        const password = '1234'
        const createUser = new CreateUser(repo)
        
        createUser.execute({ email, password })

        expect(repo.add).toHaveBeenCalledOnce()
    })

    // This is an integration test
    test(`user is added.`, async () => {
        const repo = new InMemoryUserRepository()
        const theUser = { email: 'foo@bar.com', password: '1234' }
        const createUser = new CreateUser(repo)

        createUser.execute(theUser)
        const createdUser = await repo.findByEmail(theUser.email)

        expect(createdUser).instanceOf(User)
        expect(createdUser!.email).toBe(theUser.email)
    })
})