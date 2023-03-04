import { beforeEach, expect, test } from 'vitest'
import User from '@domain/User'

interface UserRepository {
    signUp(user: User): void
}

class InMemoryUserRepository implements UserRepository {
    signUp(): void { }
}
class SignUpUser {
    constructor(private userRepository: UserRepository) { }

    execute(user: User) {
        this.userRepository.signUp(user)
    }
}

let signUpUser: SignUpUser
let user: User

beforeEach(() => {
    signUpUser = new SignUpUser(new InMemoryUserRepository())
    user = new User('foo@bar.com', '1234')
})

test(`user can sign up`, () => {
    
    
    expect(() => signUpUser.execute(user)).not.toThrowError()
})