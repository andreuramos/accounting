import { Result } from '@domain/shared/Result'
import { UseCaseError } from '../UseCaseError'

export namespace LoginErrors {

    export class WrongUserOrPassword extends Result<UseCaseError> {
        constructor() {
            super(false, {
                message: 'Wrong user or password.'
            })
        }
    }
}