import { beforeEach, expect, test } from 'vitest'

interface UserRepository {
    signUp(user: User): void
}

class InMemoryUserRepository implements UserRepository {
    signUp(): void { }
}

class User {
    constructor(email: string, password: string) {
        if (!email || !password) {
            throw 'User needs an email and a password'
        }
    }
}

class SignUpUser {
    constructor(private userRepository: UserRepository) { }

    execute(user: User) {
        this.userRepository.signUp(user)
    }
}

let signUpUser: SignUpUser

beforeEach(() => {
    signUpUser = new SignUpUser(new InMemoryUserRepository())
})

test(`user can sign up with an email and a password`, () => {
    const anyUser = new User('foo@bar.com', '1234')
    
    expect(() => signUpUser.execute(anyUser)).not.toThrowError()
})

test(`user cannot sign up without an email`, () => {
    const wrongEmail = ''
    const anyPassword = '1234'

    expect(() => signUpUser.execute(new User(wrongEmail, anyPassword))).toThrowError()
})

test(`user cannot sign up without a password`, () => {
    const anyEmail = 'foo@bar.com'
    const wrongPassword = ''

    expect(() => signUpUser.execute(new User(anyEmail, wrongPassword))).toThrowError()
})