import { Result } from '@domain/shared/Result'

interface ApplicationError {
    message: string
    error?: any
}

export namespace ApplicationErrors {

    export class UnexpectedError extends Result<ApplicationError> {
        constructor(error: any) {
            super(false, {
                message: 'An unexpected error occurred.',
                error
            } as ApplicationError)
            console.log(`[ApplicationError]: An unexpected error occurred.`)
            console.error(error)
        }
    }

}