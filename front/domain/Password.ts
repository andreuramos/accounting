import { ValueObject } from './shared'

interface PasswordProps {
    value: string
}

export class Password extends ValueObject<PasswordProps> {
    private constructor(props: PasswordProps) {
        super(props)
    }

    get value(): string {
        return this.props.value
    }

    public static create(props: PasswordProps) {
        if (!props.value) {
            throw 'Password cannot be empty.'
        }
        
        return new Password(props)
    }
}