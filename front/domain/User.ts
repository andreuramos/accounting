import { Guard, GuardArgumentCollection } from '@helpers/Guard'
import { Email } from './Email'
import { Password } from './Password'
import { Entity } from './shared/Entity'

interface UserProps {
    email: Email
    password: Password
}

export class User extends Entity<UserProps> {
    private constructor(props: UserProps) {
        super(props)
    }

    get email(): string {
        return this.props.email.value
    }

    public static create(email: Email, password: Password) {
        const guardedProps: GuardArgumentCollection = [
            { argument: email, argumentName: 'email'},
            { argument: password, argumentName: 'password'}
        ]

        const guardResult = Guard.againstNullOrUndefinedBulk(guardedProps)

        if (!guardResult.succeeded) {
            throw 'User needs an email and a password.'
        }

        return new User({ email, password })
    }
}
