<?php


namespace M4bTool\Audio\Tag;


use M4bTool\Audio\Tag;
use M4bTool\Parser\Mp4ChapsChapterParser;
use SplFileInfo;

class ChaptersFromTxtFile implements TagImproverInterface
{

    /**
     * @var Mp4ChapsChapterParser
     */
    private $chapterParser;
    private $chaptersContent;

    public function __construct(Mp4ChapsChapterParser $chapterParser = null, $chaptersContent = null)
    {
        $this->chapterParser = $chapterParser;
        $this->chaptersContent = $chaptersContent;
    }

    /**
     * Cover constructor.
     * @param SplFileInfo $reference
     * @param null $fileName
     * @return ChaptersFromTxtFile
     */
    public static function fromFile(SplFileInfo $reference, $fileName = null)
    {
        $path = $reference->isDir() ? $reference : new SplFileInfo($reference->getPath());
        $fileName = $fileName ? $fileName : "chapters.txt";
        $fileToLoad = new SplFileInfo($path . DIRECTORY_SEPARATOR . $fileName);
        if ($fileToLoad->isFile()) {
            return new static(new Mp4ChapsChapterParser(), file_get_contents($fileToLoad));
        }
        return new static();
    }


    /**
     * @param Tag $tag
     * @return Tag
     */
    public function improve(Tag $tag): Tag
    {
        if ($this->chapterParser !== null && trim($this->chaptersContent) !== "") {
            $tag->chapters = $this->chapterParser->parse($this->chaptersContent);
        }
        return $tag;
    }
}