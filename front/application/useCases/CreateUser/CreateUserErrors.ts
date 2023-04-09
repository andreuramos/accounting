import { Result } from '@domain/shared/Result'
import { UseCaseError } from '../UseCaseError'

export namespace CreateUserErrors {

    export class UserAlreadyExists extends Result<UseCaseError> {
        constructor() {
            super(false, {
                message: 'User already exists.'
            })
        }
    }

    export class GenericError extends Result<UseCaseError> {
        constructor(message?: string) {
            super(false, {
                message: message ?? 'User creation error.'
            })
        }
    }
}