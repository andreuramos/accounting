import { Guard } from '@domain/shared/Guard'
import { ValueObject } from './shared'
import { Result } from './shared/Result'

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

    public static create(password: string): Result<Password> {
        // Valorar throw dins Guard.
        // Mirar guard clauses
        // Melón Errores
        const guardResult = Guard.againstNullOrUndefined(password, 'password')
        if (!guardResult.succeeded) {
            return Result.fail<Password>(guardResult.message)
        }

        if (!this.isAppropriateLength(password)) {
            return Result.fail<Password>(`Password needs to have at least ${Password.MIN_LENGTH} characters.`)
        }

        return Result.ok<Password>(new Password({ value: password }))
    }
}