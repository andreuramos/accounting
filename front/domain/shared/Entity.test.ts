import { describe, expect, test } from 'vitest'
import { Entity } from './Entity'
import { UniqueEntityId } from './UniqueEntityId'

interface EntityMockProps {
    value: string
}

class EntityMock extends Entity<EntityMockProps> {
    constructor(props: EntityMockProps, id?: UniqueEntityId) {
        super(props, id)
    }
}

describe(`Entity class`, () => {
    test(`an entity is equal when is compared to itself.`, () => {
        const mock = new EntityMock({ value: 'Foo'})

        expect(mock.equals(mock)).toBe(true)
    })

    test(`two entities with the same props are different.`, () => {
        const mock1 = new EntityMock({ value: 'Foo' })
        const mock2 = new EntityMock({ value: 'Foo' })

        expect(mock1.equals(mock2)).toBe(false)
    })
})