import { ValueObject } from './shared'
import { Result } from './shared/Result'

interface EmailProps {
    value: string
}

export class Email extends ValueObject<EmailProps> {
    private constructor(props: EmailProps) {
        super(props)
    }

    get value(): string {
        return this.props.value
    }

    private static isValid(email: string): boolean {
        if (/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(email)) {
            return true
        }
        return false
    }

    public static create(email: string): Result<Email> {

        if (!this.isValid(email)) {
            return Result.fail<Email>('Email is not valid.')
        }
        return Result.ok<Email>(new Email({ value: email }))
    }
}