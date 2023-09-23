<?php

namespace Tests\Unit;

use App\Actions\NotePlan\ExtractBlocks;
use PHPUnit\Framework\TestCase;

class ExtractBlocksTest extends TestCase
{
    public function test_extract_blocks(): void
    {
        $markdown = "
# Test NotePlan Publish
---
Shared URL: [Shared Note 🌐](https://sharednote.space/n/vdlCL7R4j5OPgXXZ)
Last Updated: 2023-04-23 7:28:31 PM
Quick Links: [Republish](noteplan://x-callback-url/runPlugin?pluginID=asktru.ShareNote&command=publish) [Unpublish](noteplan://x-callback-url/runPlugin?pluginID=asktru.ShareNote&command=unpublish)
---

## Long Text
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin at dolor ut ligula auctor commodo. Nulla nunc mauris, tincidunt et malesuada eu, mollis quis nisl. Praesent sagittis orci et lobortis dapibus. Curabitur mattis risus eu dui egestas pellentesque. Aliquam erat volutpat. Quisque congue leo ut purus viverra efficitur. Sed accumsan dui vitae nulla lacinia egestas. Cras a odio eu urna malesuada blandit. Donec hendrerit, purus ultricies pretium lobortis, magna turpis laoreet ex, eget interdum quam turpis eget elit. Nunc et quam purus. Cras vitae elit dolor. Sed ut justo ullamcorper odio imperdiet aliquet. Maecenas ut massa et tortor bibendum mollis non non justo. Maecenas sit amet metus id ante accumsan rhoncus et sit amet nisl. Nam aliquet eget leo nec sagittis. Curabitur quis tempor purus. END OF FIRST PARAGRAPH.
Duis quis nisi felis. Sed non ullamcorper nisi. Cras finibus pharetra elit, ac vulputate nunc consectetur id. Quisque fermentum non nisl eu tincidunt. Morbi tempus ex non ultricies lobortis. Donec non erat in ipsum placerat feugiat. Sed vulputate finibus vestibulum. Quisque vel euismod velit, eu pulvinar quam. Ut finibus pellentesque magna, quis egestas felis ullamcorper id. **END OF SECOND**
Duis eget semper quam. Suspendisse ac urna tristique, malesuada quam a, consectetur libero. In laoreet leo est, eu tempor velit sodales vitae. Nunc pharetra porttitor mauris. Ut fringilla ante in ante dictum, porta rhoncus enim elementum. Nam non nisi tempor, egestas neque a, malesuada ligula. END OF THIRD
Vivamus condimentum finibus lacus. Sed consequat condimentum diam, sed finibus odio aliquam nec. Fusce velit orci, viverra sit amet lectus sit amet, finibus sodales sapien. Nullam tempor vitae elit eget malesuada. Aliquam eleifend tempus turpis, id volutpat purus vulputate eget. Nullam scelerisque, orci non fringilla congue, diam purus consequat libero, id aliquam mauris eros sit amet purus. Nulla ligula felis, fringilla ac velit at, varius dictum felis. Nullam bibendum mollis condimentum. Fusce laoreet tellus eu elit luctus pulvinar. Sed pharetra molestie commodo. Pellentesque risus felis, tincidunt et felis ac, suscipit sodales velit. Mauris ac gravida sem. END OF FOURTH

## Other Tests
Embedded file: ![file](Test%20NotePlan%20Online_attachments/Test%20File.txt)
Another line.

Some `content` here with ::highlight::, **bold**, *italic*, ~underline~, ~~strikethrought~~ and other formatting. And [links](https://duckduckgo.com/) of course.
And another line.

Let's have %% an inline %% comment here.
And let's have an end line comment here. // Because they are so cool.
| Heading | One | Two |
| ------- | --- | --- |
| Cell | First | Second |
| Another | Third | Forth |

## Subheading 2
### Subheading 3
#### Subheading 4
- Some text ~here~
- And ~~here~~
### Task List
* Not done ^gxph3h
> Quote level 1
    * Subtask level 2 [with link](https://duckduckgo.com/)
    > Quote level 2
        * Sub sub task with **bold** and *italic* text and some `code` and %% inline %% comments
        > Quote level 3
        * Another sub sub task
            * Deeper
            > Quote level 4
                * Even deeper
                    * And even deeper
    * Subtask level 2 again
        * [x] Done at level 3
        * [-] Canceled at level 3
* Scheduled >2023-12-01

## Checklists
+ Not done
    + Indented
+ [x] Done
+ [-] Canceled

### Unordered list
- Like
    - Multi
    - Level
        - Third
        - Too
- Lists

### Numbered list
1. Numbered
2. Lists
3. Too
4. Four
5. Five
6. Six
7. Seven
8. Eight
9. Nine
10. Ten
11. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed luctus justo at nibh mattis, ut volutpat lorem ullamcorper. Quisque laoreet nisi nisi, pellentesque molestie eros convallis nec. Donec commodo nibh leo, ut ullamcorper nisl molestie nec.

---

### Code snippet
```
var code = 123;
for (x in y) snippet(code, x);
```


### Quote
Two paragraphs:
> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed luctus justo at nibh mattis, ut volutpat lorem ullamcorper. Quisque laoreet nisi nisi, pellentesque molestie eros convallis nec. Donec commodo nibh leo, ut ullamcorper nisl molestie nec.
    > **Indented.** Curabitur massa erat, bibendum venenatis egestas in, eleifend facilisis odio. Phasellus quam lacus, porta tincidunt sem vitae, tempus pharetra tortor. Cras in erat nec purus eleifend lacinia vitae ac sem. Aliquam tincidunt tincidunt pellentesque. Nullam pharetra magna vitae pharetra pulvinar. Suspendisse a velit et mauris accumsan venenatis vel accumsan tortor. Sed nec velit eros. Nunc accumsan ipsum non pulvinar tempor.
Final text.
        ";

        $action = new ExtractBlocks();
        $blocks = $action->execute($markdown);

        die(json_encode($blocks));

        $this->assertTrue(true);
    }
}
