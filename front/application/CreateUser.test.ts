import { describe, expect, test } from 'vitest'
import { mock } from 'vitest-mock-extended'
import { User } from '@domain'
import InMemoryUserRepository from '@infrastructure/InMemoryUserRepository'
import { CreateUser } from './CreateUser'
import UserRepository from '@domain/UserRepository'


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
    test(`user is added.`, () => {
        const repo = new InMemoryUserRepository()
        const theUser = { email: 'foo@bar.com', password: '1234' }
        const createUser = new CreateUser(repo)

        createUser.execute(theUser)

        expect(repo.list()[0]).instanceOf(User)
        expect(repo.list()[0].email).toBe(theUser.email)
    })
})