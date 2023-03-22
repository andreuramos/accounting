import { ValueObject } from './shared'

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

    public static create(email: string): Email {
        if (/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(email)) {
            return new Email({ value: email })
        }
        throw 'Email has wrong structure.'
    }
}