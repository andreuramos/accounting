import { Email } from './Email'
import { Password } from './Password'
import { Entity } from './shared/Entity'

interface UserProps {
    email: Email
    password: Password
}

export default class User extends Entity<UserProps> {
    private constructor(props: UserProps) {
        super(props)
    }

    get email(): string {
        return this.props.email.value
    }

    public static create(email: Email, password: Password) {
        if (!email || !password) {
            throw 'User needs an email and a password.'
        }
        return new User({ email, password })
    }
}
