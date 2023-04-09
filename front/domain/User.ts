import { Guard, GuardArgumentCollection } from '@domain/shared/Guard'
import { Email } from './Email'
import { Password } from './Password'
import { Entity } from './shared/Entity'
import { Result } from './shared/Result'

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
            return Result.fail<User>(guardResult.message)
        }

        const user = new User({ email, password })

        return Result.ok<User>(user)
    }
}
