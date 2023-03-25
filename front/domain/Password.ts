import { Guard } from '@helpers/Guard'
import { ValueObject } from './shared'

interface PasswordProps {
    value: string
}

export class Password extends ValueObject<PasswordProps> {
    static MIN_LENGTH = 4

    private constructor(props: PasswordProps) {
        super(props)
    }

    get value(): string {
        return this.props.value
    }

    static isAppropriateLength(value: string): boolean {
        return value.length >= Password.MIN_LENGTH
    }

    public static create(password: string) {
        const guardResult = Guard.againstNullOrUndefined(password, 'password')
        if (!guardResult.succeeded) {
            throw guardResult.message
        }

        if (!this.isAppropriateLength(password)) {
            throw `Password needs to have at least ${Password.MIN_LENGTH} characters.`
        }
        
        return new Password({ value: password })
    }
}