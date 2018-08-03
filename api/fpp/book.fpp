namespace Book\Domain\Model\Review {
    data ReviewId = ReviewId deriving (Uuid);
    data Body = String deriving (FromString, ToString);
    data Rating = Int deriving (FromScalar, ToScalar);
    data Author = String deriving (FromString, ToString);
}

namespace Book\Domain\Model\Review\Command {
    data PostReview = PostReview {
        \Book\Domain\Model\Review\ReviewId $id,
        ?\Book\Domain\Model\Review\Body $body,
        \Book\Domain\Model\Review\Rating $rating,
        \Book\Domain\Model\Review\Author $author
    } deriving (Command);
}

namespace Book\Domain\Model\Review\Event {
    data ReviewWasPosted = ReviewWasPosted {
        \Book\Domain\Model\Review\ReviewId $id,
        ?\Book\Domain\Model\Review\Body $body,
        \Book\Domain\Model\Review\Rating $rating,
        \Book\Domain\Model\Review\Author $author
    } deriving (AggregateChanged);
}

namespace Book\Domain\Model\Book {
    data BookId = BookId deriving (Uuid);
    data Isbn = String deriving (FromString, ToString);
    data Title = String deriving (FromString, ToString);
    data Description = String deriving (FromString, ToString);
    data Author = String deriving (FromString, ToString);
}

namespace Book\Domain\Model\Book\Command {
    data CreateBook = CreateBook {
        \Book\Domain\Model\Book\BookId $id,
        \Book\Domain\Model\Book\Isbn $isbn,
        \Book\Domain\Model\Book\Title $title,
        \Book\Domain\Model\Book\Description $description,
        \Book\Domain\Model\Book\Author $author,
    } deriving (Command);
}

namespace Book\Domain\Model\Book\Event {
    data BookWasCreated = BookWasCreated {
        \Book\Domain\Model\Book\BookId $id,
        \Book\Domain\Model\Book\Isbn $isbn,
        \Book\Domain\Model\Book\Title $title,
        \Book\Domain\Model\Book\Description $description,
        \Book\Domain\Model\Book\Author $author,
    } deriving (AggregateChanged);
}
