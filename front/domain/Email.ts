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

    public static create(email: string): Result<Email> {
        if (/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(email)) {
            return Result.ok<Email>(new Email({ value: email }))
        }
        return Result.fail<Email>('Email has wrong structure.')
    }
}