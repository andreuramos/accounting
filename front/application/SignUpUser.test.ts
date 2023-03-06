import { expect, test, vi } from 'vitest'
import User from '@domain/User'
import InMemoryUserRepository from '@infrastructure/InMemoryUserRepository'
import SignUpUser from './SignUpUser'

test(`signUpUser calls repo's sign up method`, () => {
    const repo = new InMemoryUserRepository()
    repo.add = vi.fn()
    const signUp = new SignUpUser(repo)
    const someUser = new User('foo@bar.com', '1234')
    
    signUp.execute(someUser)

    expect(repo.add).toHaveBeenCalledOnce()
})

test(`user is registered after signUp`, () => {
    const repo = new InMemoryUserRepository()
    const signUp = new SignUpUser(repo)
    const theUser = { email: 'foo@bar.com', password: '1234' }

    signUp.execute(theUser)

    expect(repo.list()).toStrictEqual([ theUser ])
})